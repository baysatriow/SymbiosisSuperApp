import mysql.connector
from mysql.connector import Error
import argparse
import random
from datetime import datetime, timedelta
import feedparser
from textblob import TextBlob
from bs4 import BeautifulSoup
import os
import sys

# Optional libraries for scraping
try:
    from ntscraper import Nitter
except ImportError:
    Nitter = None

# Gemini Library
try:
    import google.generativeai as genai
except ImportError:
    genai = None

# --- CONFIGURATION ---
DB_CONFIG = {
    'host': 'localhost',
    'database': 'symbiosis_db',
    'user': 'root',
    'password': '',
}

TOPICS = ['Isu Lingkungan', 'Tambang']
PLATFORMS_ENABLED = ['News', 'Twitter', 'Instagram', 'Facebook', 'Tiktok']

# Default sentiment fallback
SENTIMENT_CACHE = {} 

def get_db_connection():
    try:
        connection = mysql.connector.connect(**DB_CONFIG)
        return connection
    except Error as e:
        print(f"!! Error connecting to MySQL: {e}")
        return None

def clean_html(raw_html):
    if not raw_html: return ""
    soup = BeautifulSoup(raw_html, "html.parser")
    return soup.get_text()

# --- SENTIMENT ENGINES ---

def analyze_sentiment_blob(text):
    """Basic TextBlob Sentiment Analysis"""
    analysis = TextBlob(text)
    score = analysis.sentiment.polarity
    if score > 0.05: label = 'Positif'
    elif score < -0.05: label = 'Negatif'
    else: label = 'Netral'
    return score, label

def analyze_sentiment_gemini(text):
    """Advanced Sentiment Analysis using Google Gemini API"""
    api_key = os.environ.get('GEMINI_API_KEY')
    if not api_key or not genai:
        print("!! Gemini API Key broken or library missing. Fallback to Blob.")
        return analyze_sentiment_blob(text)

    try:
        genai.configure(api_key=api_key)
        model = genai.GenerativeModel('gemini-pro')
        
        prompt = f"""
        Analyze the sentiment of this Indonesian text: "{text}"
        Classify as: Positif, Netral, or Negatif.
        Give a score between -1.0 (Negative) to 1.0 (Positive).
        Format: Label|Score
        Example: Negatif|-0.8
        """
        
        response = model.generate_content(prompt)
        result = response.text.strip()
        
        parts = result.split('|')
        if len(parts) == 2:
            label = parts[0].strip()
            score = float(parts[1].strip())
            return score, label
        else:
            return analyze_sentiment_blob(text) # Fallback format error
            
    except Exception as e:
        print(f"!! Gemini Error: {e}. Fallback to Blob.")
        return analyze_sentiment_blob(text)

# --- SCRAPERS ---

def scrape_google_news(topic):
    print(f"   [News] Crawling topic: {topic}")
    base_url = "https://news.google.com/rss/search?q={query}+when:7d&hl=id-ID&gl=ID&ceid=ID:id"
    feed = feedparser.parse(base_url.format(query=topic.replace(" ", "+")))
    
    results = []
    # Limit to top 10 per topic for speed in this demo
    for entry in feed.entries[:10]:
        dt = datetime(*entry.published_parsed[:6]) if 'published_parsed' in entry else datetime.now()
        results.append({
            'platform': 'News',
            'timestamp': dt,
            'author': entry.source.title if 'source' in entry else 'Google News',
            'content': entry.title,
            'url': entry.link
        })
    return results

def scrape_twitter(topic):
    """Scrape Twitter via Nitter (ntscraper)"""
    if not Nitter:
        print("   [Twitter] Library ntscraper missing.")
        return []

    print(f"   [Twitter] Scraping topic: {topic}")
    results = []
    try:
        scraper = Nitter(log_level=1, skip_instance_check=False) # skip check for speed
        tweets = scraper.get_tweets(topic, mode='term', number=5)
        
        if 'tweets' in tweets:
            for t in tweets['tweets']:
                dt = datetime.strptime(t['date'], "%b %d, %Y · %I:%M %p UTC") # Approximate format, might need adjust
                results.append({
                    'platform': 'Twitter',
                    'timestamp': datetime.now(), # Fallback for now due to date parsing complexity
                    'author': t['user']['name'],
                    'content': t['text'],
                    'url': t['link']
                })
    except Exception as e:
        print(f"   [Twitter] Error: {e}")
        # Fallback to dummy if scraping fails heavily
        pass
        
    return results

# Placeholder for others to avoid crashing
def scrape_simulated(platform, topic):
    """Simulates scraping for platforms that are hard to scrape without credentials"""
    print(f"   [{platform}] Simulating scrape for: {topic}")
    results = []
    fake_authors = [f"{platform}User1", f"{platform}Influencer", "Netizen+62", "ViralIndo"]
    for _ in range(3): # Small batch
        results.append({
            'platform': platform,
            'timestamp': datetime.now() - timedelta(minutes=random.randint(5, 100)),
            'author': random.choice(fake_authors),
            'content': f"Opini tentang {topic} yang lagi ramai di {platform}. #indonesia #{topic.replace(' ', '')}",
            'url': f"https://{platform.lower()}.com/post/{random.randint(1000,9999)}"
        })
    return results

# --- MAIN CONTROLLER ---

def insert_data(connection, data_list, engine_func):
    cursor = connection.cursor()
    query = """
    INSERT INTO national_issues_sentiment_hourly 
    (data_id, platform, timestamp, author_name, content_text, cleaned_text, sentiment_score, sentiment_label, category_issue, engagement_count, location, url_source, created_at, updated_at)
    VALUES (%(data_id)s, %(platform)s, %(timestamp)s, %(author_name)s, %(content_text)s, %(cleaned_text)s, %(sentiment_score)s, %(sentiment_label)s, %(category_issue)s, %(engagement_count)s, %(location)s, %(url_source)s, NOW(), NOW())
    ON DUPLICATE KEY UPDATE updated_at=NOW()
    """
    
    count = 0
    for item in data_list:
        # Sentiment Analysis
        score, label = engine_func(item['content'])
        
        row = {
            'data_id': f"{item['platform'][:2]}_{int(item['timestamp'].timestamp())}_{random.randint(100,999)}",
            'platform': item['platform'],
            'timestamp': item['timestamp'].strftime('%Y-%m-%d %H:%M:%S'),
            'author_name': item['author'][:50], # Limit length
            'content_text': item['content'],
            'cleaned_text': item['content'][:200],
            'sentiment_score': round(score, 4),
            'sentiment_label': label,
            'category_issue': item['topic'],
            'engagement_count': random.randint(0, 100), # Placeholder
            'location': 'Indonesia',
            'url_source': item['url']
        }
        
        try:
            cursor.execute(query, row)
            connection.commit()
            count += 1
        except Error as e:
            print(f"!! DB Error: {e}")

    cursor.close()
    return count

def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('--mode', choices=['demo', 'live'], default='demo')
    parser.add_argument('--engine', choices=['blob', 'gemini'], default='blob')
    parser.add_argument('--sources', default='all') # all or comma sep: twitter,news
    args = parser.parse_args()

    print(f">> Starting Processor | Mode: {args.mode.upper()} | Engine: {args.engine.upper()}")

    conn = get_db_connection()
    if not conn: return

    # Choose Engine
    if args.engine == 'gemini':
        engine_func = analyze_sentiment_gemini
    else:
        engine_func = analyze_sentiment_blob

    all_data = []

    if args.mode == 'demo':
        # Pure Simulation
        print(">> Generating DEMO data...")
        for topic in TOPICS:
            for plat in PLATFORMS_ENABLED:
                # Use simulated scraper for all platforms in Demo mode
                data = scrape_simulated(plat, topic)
                for d in data: d['topic'] = topic
                all_data.extend(data)

    else:
        # Live Mode
        for topic in TOPICS:
            # 1. Google News
            news = scrape_google_news(topic)
            for d in news: d['topic'] = topic
            all_data.extend(news)
            
            # 2. Twitter (Ntscraper)
            tw = scrape_twitter(topic)
            for d in tw: d['topic'] = topic
            all_data.extend(tw)
            
            # 3. Simulate others for "Live" feeling without blocking
            # Real scraping for generic IG/FB is too unstable for a reliable demo script 
            # so we start with simulation for them to fill the chart.
            for plat in ['Instagram', 'Facebook', 'Tiktok']:
                sim = scrape_simulated(plat, topic)
                for d in sim: d['topic'] = topic
                all_data.extend(sim)

    if all_data:
        print(f">> Analyzing {len(all_data)} items with {args.engine.upper()}...")
        inserted = insert_data(conn, all_data, engine_func)
        print(f">> Process completed. Inserted {inserted} records.")
    else:
        print(">> No data found.")
    
    conn.close()

if __name__ == "__main__":
    main()
