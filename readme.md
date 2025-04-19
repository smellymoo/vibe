# VIBE 🔥 
(Very Intelligent; Barely Executable)

For **YOLO-Driven Development**, meet **VIBE** - the scripting language where your code syntax is more of a suggestion than a requirement.
Write PHP-ish, Python-y, or just keyboard smash - Vibe will sort it out. Probably.

Make coding fun again. Specially useful for quick throw-away scripts to get sh*t done.

# Install

**install:**
./install.sh

**to uninstall (coward!):**
./install.sh --remove

**to try out without installing:**
install with: ./setup.sh
then run vibe script with: php ./interpreter.php ./examples/run_tests.vibe

# Usage example:

echo "ask a question:" + newline
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

