# Usenet Archiving Tool

This code is for archiving Usenet discussions, not downloading files. Newsgroup posts are saved under the authors name and email, instead of a subject / thread format. It's possible to download all of the post on a group's NNTP server, or to select a subset of those posts. Just place usenet.py in an empty directory and run it using Python.

It uses Minixed to show the newsgroup's directory, but has been modififed to handle UTF-8 character encoding.

(https://github.com/lorenzos/Minixed)

(UPDATE) New search feature!

(UPDATE) An example archive for the group alt.magick can be found at:

https://alt-magick.com

(NOTE)   NNTP is a very slow protocol. In order to download every post on alt.magick I had to leave this program running for over 24 hours. 

(UPDATE) New script, 'update.py' will grab new articles and add them to the archive.

(UPDATE) Add this to 'crontab -e' to automatically check for updates every 10 minuets:

*/10 * * * * python update.py > /dev/null 2>&1


