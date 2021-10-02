#!/usr/bin/python
# Copyright (c) 2021 Corey White

import nntplib
import string
import re

s = nntplib.NNTP('news.eweka.nl', user='f3c01679dd3605a', password='')
resp, count, first, last, name = s.group('alt.magick')
print('Group', name, 'has', count, 'articles, range', first, 'to', last)
cnt =  int(last)
stop = 0 # When (1) program terminates on duplicate Message-IDs.
while cnt >= int(first):
	loop = 0
	while loop == 0:
		try:
			resp2, num2, id2, list = s.body(str(cnt))
			r, n, id3, headers = s.head(id2)
			loop =  1
		except:
			cnt = cnt - 1
	check = 1
        author = "From: "
	subject = "Subject: "
	date = "Date: "
	id = "Message-ID: "

	for check1 in headers:
		if check1.startswith("From: "):
			author = check1
		else:
			check = 0

	for check2 in headers:
		if check2.startswith("Subject: "):
			subject = check2
		else:
			check = 0

	for check3 in headers:
		if check3.startswith("Date: "):
			date = check3
		else:
			check = 0

	for check4 in headers:
		if check4.startswith("Message-ID: "):
			id = check4
			if stop == 1:
				with open("history.txt", 'a+') as f:
					for lastmessage in f:
						if(id == lastmessage.rstrip()):
							print("Article found.")
							s.quit()
							quit()
			history = open("history.txt", 'a+')
				history.write("%s\n" % id)
				history.close()
		else:
			check = 0
	filename = author[6:]
	filename = filename.decode('utf-8','ignore').encode("utf-8")
	filename = filename.replace('r/', '')
	filename = ' '.join(filename.split())
	filename = filename.strip()
	if len(filename) and filename[0] != "'" and filename[0] != '"':
		filename = '"' + filename + '"'
	try:
		file = open(filename, 'a+')
	except:
		file = open("unknown", "a+")

	file.write("%s\n\n" % author);
	file.write("%s\n\n" % subject);
	file.write("%s\n\n" % date);
	file.write("%s\n\n" % id);

	for line in list:
		file.write(line[:80])
		file.write("\n")

	file.write("\n\n")
	file.write("###")
	print("%s" % str(cnt))
	cnt = cnt - 1
print ("\n Done.")
s.quit()


#!/usr/bin/python
# Copyright (c) 2021 Corey White

import nntplib
import string
import re

s = nntplib.NNTP('news.eweka.nl', user='f3c01679dd3605ae', password='')
resp, count, first, last, name = s.group('alt.magick')
print('Group', name, 'has', count, 'articles, range', first, 'to', last)
cnt =  int(last)
stop = 0 # When (1) program terminates on duplicate Message-IDs.
while cnt >= int(first):
	test1 = 0
	while test1 == 0:
		try:
			resp2, num2, id2, list = s.body(str(cnt))
			r, n, id3, headers = s.head(id2)
			test1 = 1
		except:
			cnt = cnt - 1
			test1 = 0


	global check
	check = 0
	
	for check1 in headers:
		if check1.startswith("From: "):
			check = check + 1
	for check2 in headers:
		if check2.startswith("Subject: "):
			check = check + 1
	for check3 in headers:
		if check3.startswith("Date: "):
			check = check + 1
	for check4 in headers:
		if check4.startswith("Message-ID: "):
			check = check + 1
			if stop == 1:
				with open("history.txt", 'a+') as f:
					for lastmessage in f:
						if(check4 == lastmessage.rstrip()):
							print("Article found.")
							s.quit()
							quit()
	if check == 4:

		another1 = 0

		for item in headers:
			if item.startswith("From: ") and another1 == 0:
				#print("%s\n" % item)
				global author
				author = item[6:]
				author = author.decode('utf-8','ignore').encode("utf-8")
				author = author.replace('/', '')
				author = ' '.join(author.split())
				author = author.strip()
				if len(author) and author[0] != "'" and author[0] != '"':
						author = '"' + author + '"'
				file = open(author, 'a')
				file.write("\n")
				file.write("%s\n\n" % item)
				file.close()
				another1 = 1

		another2 = 0

		for item2 in headers:
	
			if item2.startswith("Subject: ") and another2 == 0:
				#print("%s\n" % item2)
				global author
				file = open(author, 'a')
				file.write("%s\n\n" % item2)
				file.close()
				another2 = 1

		another3 = 0

		for item3 in headers:
	
			if item3.startswith("Date: ") and another3 == 0:
				#print("%s\n" % item3)
				global author
				file = open(author, 'a')
				file.write("%s\n\n" % item3)
				file.close()
				another3 = 1

		another4 = 0

		for item4 in headers:
	
			if item4.startswith("Message-ID: ") and another4 == 0:
				#print("%s\n" % item4)
				global author
				file = open(author, 'a')
				file.write("%s\n\n" % item4)
				file.close()
				file = open("history.txt", 'a')
				file.write("%s\n" % item4)
				file.close()
				another4 = 1

		doublecheck = another1 + another2 + another3 + another4

		if doublecheck == 4:
			for line in list:
				#print(line[:80])
				#print("\n")
				global author
				file = open(author, 'a')
				file.write(line[:80])
				file.write("\n")
				file.close()
				doublecheck = 0

	print("%s" % str(cnt))
	cnt = cnt - 1
print ("\n Done.")
s.quit()
