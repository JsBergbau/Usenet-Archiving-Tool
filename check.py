import os
import time
from datetime import datetime, timedelta

while 1:

	dt = datetime.now() + timedelta(minutes=5)

	while datetime.now() < dt:
		time.sleep(1)

	os.system("python /var/www/html/alt-magick.com/update.py")	
