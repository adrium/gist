# Emoji Rendering in Ubuntu Bionic

The [fontconfig bug](https://launchpad.net/bugs/1674730) in `fonts-emojione=2.2.6+16.10.20160804-0ubuntu2` is not yet fixed.

Compare the rendering of different emojis "❤ 🏁 😀 🙉 🍇" below:

![Rendering with default config](emoji-bad.png)

Rendering with default config.

![Rendering with custom emoji config](emoji-good.png)

Rendering with [custom emoji config](56-emoji.conf).
