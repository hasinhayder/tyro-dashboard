import re
from bs4 import BeautifulSoup

# Paths
old_doc_path = '/Users/hasinhayder/Projects/laravel/tyro-dev/tyro-dashboard-landing/doc.html'
new_doc_path = '/Users/hasinhayder/Projects/laravel/tyro-dev/tyro-dashboard-landing/new-design/doc.html'

with open(old_doc_path, 'r', encoding='utf-8') as f:
    old_soup = BeautifulSoup(f.read(), 'html.parser')

with open(new_doc_path, 'r', encoding='utf-8') as f:
    new_html = f.read()

new_soup = BeautifulSoup(new_html, 'html.parser')

# Get doc-sidebar and doc-content from old
old_layout = old_soup.find('div', class_='doc-layout')

if not old_layout:
    print("Could not find doc-layout in old doc")
    exit(1)

# Modify old_layout URLs
for a in old_layout.find_all('a', href=True):
    if a['href'] == 'index.html':
        a['href'] = 'index2.html'
    elif a['href'] == 'index.html#features':
        a['href'] = 'index2.html#features'

for img in old_layout.find_all('img', src=True):
    if img['src'].startswith('images/'):
        img['src'] = '../' + img['src']

for a in old_layout.find_all('a', href=True):
    if a['href'].startswith('images/'):
         a['href'] = '../' + a['href']

# Find doc-layout in new
new_layout = new_soup.find('div', class_='doc-layout')
if new_layout:
    new_layout.clear()
    for child in old_layout.contents:
        new_layout.append(child)

# Now about the spotlight search JS
# In new_doc_path, the spotlight search expects a searchIndex variable.
# Let's adapt it to build dynamically like in old.
script_tags = new_soup.find_all('script')
target_script = None
for s in script_tags:
    if s.string and 'searchIndex =' in s.string:
        target_script = s
        break

if target_script:
    new_script = target_script.string
    # Replace the hardcoded searchIndex array with a dynamic one
    replacement = """
                        const docSections = Array.from(document.querySelectorAll('.doc-section')).map(section => {
                            let desc = section.querySelector('.doc-text')?.textContent.trim() || '';
                            if (desc.length > 60) desc = desc.substring(0, 60) + '...';
                            return {
                                id: section.id,
                                title: section.querySelector('.doc-section-title')?.textContent || section.id,
                                desc: desc,
                                icon: '📄'
                            };
                        });
                        const searchIndex = docSections;
    """
    new_script = re.sub(r'const searchIndex = \[\s*\{.*?\}\s*\];', replacement, new_script, flags=re.DOTALL)
    target_script.string.replace_with(new_script)

# Save the updated new_design/doc.html
modified_html = str(new_soup)
# Fix some formatting issues created by BeautifulSoup modifying script content
modified_html = modified_html.replace("&lt;", "<").replace("&gt;", ">").replace("&amp;", "&")

with open(new_doc_path, 'w', encoding='utf-8') as f:
    f.write(modified_html)

print("Successfully updated doc.html")
