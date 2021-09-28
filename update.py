#!/usr/bin/python
# Copyright (c) 2021 Corey White

import nntplib
import string
import shutil
import re
import os

start = 1

s = nntplib.NNTP('', user='', password='')
resp, count, first, last, name = s.group('')
cnt = int(last)
s.quit()

while start == 1:
	s = nntplib.NNTP('', user='', password='')
	resp, count, first, last, name = s.group('')
	resp2, num2, id2, list = s.body(str(cnt))
	r, n, id3, headers = s.head(id2)
	s.quit()

	for check in headers:
		field = check.lower()
		if field.startswith("message-id: "):
			id = check
			with open("history.txt", 'a+') as f:
				for lastmessage in f:
					if(id == lastmessage.rstrip()):
						start = 0
						if cnt == int(last):
							print("\nNothing to do.\n")
							quit()
	cnt = cnt - 1

cnt = cnt + 2

s = nntplib.NNTP('', user='', password='')
resp, count, first, last, name = s.group('')
message = "\nGroup: " + name + " has " + count + " articles, ranged from: " + first + " to " + last + ". \n\nStarting at article: " + str(cnt) + ".\n"
print(message)
s.quit()


while cnt <= int(last):
	loop = 0
	while loop == 0:
		try:
			s = nntplib.NNTP('', user='', password='')
			resp, count, first, last, name = s.group('')
			resp2, num2, id2, list = s.body(str(cnt))
			r, n, id3, headers = s.head(id2)
			s.quit()
			loop =  1
		except:
			cnt = cnt - 1
	author = "from: "
	subject = "subject: "
	date = "date: "
	id = "message-id: "

	for check1 in headers:
		field = check1.lower()
		if field.startswith("from: "):
			author = check1

	for check2 in headers:
		field = check2.lower()
		if field.startswith("subject: "):
			subject = check2

	for check3 in headers:
		field = check3.lower()
		if field.startswith("date: "):
			date = check3

	for check4 in headers:
		field = check4.lower()
		if field.startswith("message-id: "):
			id = check4
			print("### %s\n" % id)
			history = open("history.txt", 'a+')
			history.write("%s\n" % id)
			history.close()

	filename = author[6:]
	filename = filename.decode('utf-8','ignore').encode("utf-8")
	filename = filename.replace(r'/', '')
	filename = ' '.join(filename.split())
	filename = filename.strip()
	if filename[0] != "'" and filename[0] != '"':
		filename = '"' + filename + '"'

	try:
		path  = "/var/www/html/"
		path = path + filename
		file = open(path, 'a+')
	except:
		continue

	buffer = open("buffer", "w")		
	buffer.write("%s\n\n" % author);
	buffer.write("%s\n\n" % subject);
	buffer.write("%s\n\n" % date);
	buffer.write("%s\n\n" % id);

	for line in list:
		buffer.write(line[:80])
		buffer.write("\n")

	buffer.write("\n\n")
	buffer.write(file.read())
	buffer.close()
	file.close()
	buffer = open("buffer", "r")
	file = open(path, "w")
	file.write(buffer.read())
	buffer.close()

	file.close()
	os.remove("buffer")
	print("Saving: %s\n" % str(cnt))

	cnt = cnt + 1

print ("Done.")
quit()
