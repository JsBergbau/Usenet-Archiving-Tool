# Usenet Archiving Tool

This code is for archiving Usenet discussions, not downloading files. Newsgroup posts are saved under the authors name and email, instead of a subject / thread format. It's possible to download all of the post on a group's NNTP server, or to select a subset of those posts. Just place pull.py in an empty directory, configure server (optional credentials) and run it using Python. To store files on windows, there is a renaming option. 

(UPDATE) An example archive for the group alt.magick can be found at:

https://alt-magick.com

(NOTE)   NNTP is a very slow protocol. In order to download every post on alt.magick I had to leave this program running for over 24 hours. 

Run update.py to download messages.  Use the linux 'screen' command to run check.py in the background which will do live updates!
Searching is slow for entire newsgroup archives.
