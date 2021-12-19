import os
import time
from datetime import datetime, timedelta

while 1:

	dt = datetime.now() + timedelta(minutes=1)

	while datetime.now() < dt:
		time.sleep(1)

	os.system("python /var/www/html/alt-magick.com/public_html/update.py")	
