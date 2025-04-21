# VIBE 🔥 
*(Very Intelligent; Barely Executable)*

Make coding fun again. Fed up with OOP nonsense?
meet **IOP** - Intention-Oriented Programming™, 
for when you know what you want to do, just write a vague script, and **get sh*t done**.

Vibe is for **YOLO-Driven Development**, 
it has no syntax. Write PHP-ish, Python-y, or whatever `¯\_(ツ)_/¯`

# Install

```bash
# install:
1. install PHP
2. run: sudo ./install.sh

# try without installing:
1. install PHP
2. run: sudo ./setup.sh
3. run vibe script: php ./interpreter.php your_script.vibe

# uninstall (coward!):
sudo ./install.sh --remove
```
# Usage

```bash
# run your vibe script:
vibe your_script.vibe
```

# Built-in functions

```php
# prompt the LLM and return string
prompt(prompt, system_prompt)

# use LLM to categorise message [see example below]
categorise(msg, array categories)
```

# Example

```php
echo "ask a question:"
msg = get user input

categories = [
    HAPPY => happy sentiment
    NEUTRAL => neutral sentiment
    SAD => negative sentiment
]

mood = categorise(msg, categories);

switch(mood) {
    case HAPPY: order_uber_eats();
    case NEUTRAL: print("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
    case SAD: die("Ctrl+Z yourself");
}
```
