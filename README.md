# Usenet Archiving Tool

This code is for archiving Usenet discussions, not downloading binaries. It downloads all of the post on a group's NNTP server, and continues updating itself. A simple web tool allows for browsing and searching the archive.

(UPDATE) An example archive for the group alt.magick can be found at:

https://alt-magick.com

(NOTE)   NNTP is a very slow protocol. In order to download every post on alt.magick I had to leave this program running for over 24 hours.
Searching can also be slow for entire newsgroup archives.

Run update.py to download messages.  Use the linux 'screen' command to run check.py in the background which will get live updates of the
newsgroup.

Thanks to JsBergbau for his work on a fork of this project: (https://github.com/JsBergbau/Usenet-Archiving-Tool)

(NOTE) For security reasons keep python files in a directory that's not open to the web.
