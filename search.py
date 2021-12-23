#!/usr/bin/python
# Copyright (c) 2021 Corey White

import os
import sys
import fnmatch

search  = sys.argv[1].lower()
print 'Searching for "' + search + '".<br>'
print "<br>"

user_input = "/var/www/html/alt-magick.com/public_html/"
directory = os.listdir("/var/www/html/alt-magick.com/public_html/")


for fname in directory:
    if os.path.isfile(user_input + os.sep + fname):
        # Full path
        f = open(user_input + os.sep + fname, 'r')
	x = fname.replace("_", " ")
        if search in f.read():
            print("<a href='" + fname + "' target=_'blank'>" + x + "</a> <br><br>")
        f.close()
