#!/usr/bin/python
# Copyright (c) 2021 Corey White

import os
import sys
import fnmatch

search  = sys.argv[1].lower()
print 'Searching for "' + search + '".<br>'
print "<br>"

for root, dirs, files in os.walk('.'):
	for file in files:
		with open(os.path.join(root, file), "r") as auto:
			f = auto.read(500000000).lower()
			if search in f:
				user = file
				file = file.replace("/","%2F")
				file = file.replace("\\","%5C")
				file = file.replace("<","&lt;")
				file = file.replace(">","&gt;")
				file = file.replace(" ", "%20")
				file = file.replace('"', '%22')
				file = file.replace('=', '%3D')
				file = file.replace('?', '%3F')
				file = file.replace('"', '%22')
				file = file.replace('&lt;', '%3C')
				file = file.replace('&gt;', '%3E')
				file = file.replace('@', '%40')

				user = file.replace("%2F","")
				user = user.replace("%5C","")
				user = user.replace("&lt;","&lt;")
				user = user.replace("&gt;","&gt;")
				user = user.replace("%20", " ")
				user = user.replace('%22', '"')
				user = user.replace('%3D', '')
				user = user.replace('%3F', '')
				user = user.replace('%40', '@')
				user = user.replace('%3C', '&lt;')
				user = user.replace('%3E', '&gt;')
				if user.startswith('"') and user.endswith('"'):
					user = user[1:-1]
				link1 = '<a href="'
				link2 = '">'
				link3 = '</a><br>'
				trunc = (user[:64] + '..') if len(user) > 40 else user
				print link1 + file + link2 + trunc + link3
