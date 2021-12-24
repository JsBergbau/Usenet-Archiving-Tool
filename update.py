#!/usr/bin/python
# Copyright (c) 2021 Corey White

import nntplib
from email.header import decode_header
import quopri
import traceback
import os.path
import sys

########### Configuration area ########################
def getConnection():
	return nntplib.NNTP('server', user='login', password='pass') #Python2 supports in nntplib only unsecure connections. Use Stunnel to "translate" if you need access to encrypted newsserver
group = "alt.magick" #Enter the group you want to download here
codepage = "cp1252" #For German
########### Configuration area end ####################

s = getConnection()
resp, count, first, last, name = s.group(group)
message = "\nGroup: " + name + " has " + count + " articles, ranged from: " + first + " to " + last + ".\n"
print(message)
cnt =  int(last)
s.quit()
while cnt >= int(first):
	loop = 0
	while loop == 0:
		try:
			s = getConnection()
			resp, count, first, last, name = s.group(group)
			resp2, num2, id2, list = s.body(str(cnt))
			r, n, id3, headers = s.head(id2)
			s.quit()
			loop =  1
		except Exception as e:
			print("Error with connection")
			print(e)
			print(traceback.format_exc())
			cnt = cnt - 1 #Commented to retry infinite time
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

	subject = decode_header(subject)
	subjectDecoded = ""
	for part in subject:
		try:
			subjectDecoded += part[0].decode("utf-8" if part[1] == None else part[1]) + " "
		except:
			subjectDecoded += " "
	
	subjectDecoded=subjectDecoded[:-1]
	subject = subjectDecoded.encode("utf-8")
		
	#filename = author[6:]
	filename = str(cnt).zfill(6) + " " + author[6:] + " - " + subject[8:] #id[12:] + "#" +
	
	filename = filename.replace(r'/', '')
	filename = ' '.join(filename.split())
	filename = filename.strip()
	saving = ""
	for character in filename:
		if character.isalnum():
			saving += character
		if character == " ":
			saving += "_"
		if character == ".":
			saving += character
		if character =="@":
			saving += character

	saving = "/var/www/html/alt-magick.com/public_html/" + saving
	print(saving);
	if os.path.isfile(saving):
        	sys.exit()
	try:
		file = open(saving, 'a+')
	except:
		file = open("unknown", 'a+')

	file.write("### %s\n\n" % str(cnt))
	file.write("%s\n\n" % author);
	file.write("%s\n\n" % subject);
	file.write("%s\n\n" % date);
	file.write("%s\n\n" % id);

	for line in list:
		file.write(line[:8000])
		file.write("\n")

	file.write("\n\n")
	file.close()

	print("%s" % str(cnt))
	cnt = cnt - 1
print ("\n Done.")
quit()
