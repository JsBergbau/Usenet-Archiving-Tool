# Usenet Archiving Tool

This code is for archiving Usenet discussions, not downloading files. It downloads all of the post on a group's NNTP server, and continues updating itself. A simple web tool allows for browsing and searching the archive.

(UPDATE) An example archive for the group alt.magick can be found at:

https://alt-magick.com

(NOTE)   NNTP is a very slow protocol. In order to download every post on alt.magick I had to leave this program running for over 24 hours. 

Run update.py to download messages.  Use the linux 'screen' command to run check.py in the background which will do live updates!
Searching is slow for entire newsgroup archives.

Thanks to JsBergbau for his work on a fork of this project: (https://github.com/JsBergbau/Usenet-Archiving-Tool)
