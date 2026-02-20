from bs4 import BeautifulSoup

def analyze_html(file_path):
    with open(file_path, 'r', encoding='utf-8') as f:
        soup = BeautifulSoup(f.read(), 'html.parser')

    scripts = soup.find_all('script')
    print(f"Scripts in {file_path}: {len(scripts)}")
    for s in scripts:
        print(s.get_text()[:200] + "...")
        print("-" * 20)

analyze_html('/Users/hasinhayder/Projects/laravel/tyro-dev/tyro-dashboard-landing/doc.html')
analyze_html('/Users/hasinhayder/Projects/laravel/tyro-dev/tyro-dashboard-landing/new-design/doc.html')
