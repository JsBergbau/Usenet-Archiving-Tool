# Usenet Archiving Tool

This code is for archiving Usenet discussions, not downloading files. Newsgroup posts are saved under the authors name and email, instead of a subject / thread format. It's possible to download all of the post on a group's NNTP server, or to select a subset of those posts. Just place pull.py in an empty directory, configure server (optional credentials) and run it using Python. To store files on windows, there is a renaming option. 

Note: Headers are not preserved. For every message a new connection is established. An easier way to mirror a newsgroup is using Thunderbird. When setting up newsgroup use maildir format. This will save every message in a single file. Then right click on the group --> properties --> Tab synchronization and check for Offline reading.
All headers are preserved this way and firefox re-uses the connetion which makes downloading much faster. 

In nginx enable php and add in server section 
```
types {
  text/plain eml;
}
```
Then Minixed can handle the files like those from the python script. 

It uses Minixed to show the newsgroup's directory, but has been modififed to handle UTF-8 character encoding.

(https://github.com/lorenzos/Minixed)

(UPDATE) New search feature!

(UPDATE) An example archive for the group alt.magick can be found at:

https://alt-magick.com

(NOTE)   NNTP is a very slow protocol. In order to download every post on alt.magick I had to leave this program running for over 24 hours. 

(UPDATE) New script, 'update.py' will grab new articles and add them to the archive.

(UPDATE) Add this to 'crontab -e' to automatically check for updates every 10 minuets:

*/10 * * * * python update.py > /dev/null 2>&1


