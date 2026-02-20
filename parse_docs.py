import re

def analyze_html(file_path):
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    sections = re.findall(r'<section [^>]*?id="([^"]+)"[^>]*>.*?<h2[^>]*>(.*?)</h2>', content, re.DOTALL | re.IGNORECASE)
    print(f"\n--- Sections in {file_path} ---")
    for sec_id, title in sections:
        # Strip internal tags
        title = re.sub(r'<[^>]+>', '', title).strip()
        print(f"[{sec_id}] {title}")

analyze_html('/Users/hasinhayder/Projects/laravel/tyro-dev/tyro-dashboard-landing/doc.html')
analyze_html('/Users/hasinhayder/Projects/laravel/tyro-dev/tyro-dashboard-landing/new-design/doc.html')
