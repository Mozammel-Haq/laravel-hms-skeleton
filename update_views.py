import os

def update_files():
    root_dir = 'resources/views'
    count = 0
    for subdir, dirs, files in os.walk(root_dir):
        for file in files:
            if file.endswith('.blade.php'):
                filepath = os.path.join(subdir, file)
                try:
                    with open(filepath, 'r', encoding='utf-8') as f:
                        content = f.read()
                    
                    if '<div class="table">' in content:
                        new_content = content.replace('<div class="table">', '<div class="table-responsive">')
                        with open(filepath, 'w', encoding='utf-8') as f:
                            f.write(new_content)
                        print(f"Updated: {filepath}")
                        count += 1
                except Exception as e:
                    print(f"Error processing {filepath}: {e}")
    print(f"Total files updated: {count}")

if __name__ == '__main__':
    update_files()
