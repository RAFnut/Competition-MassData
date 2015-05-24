from textblob import TextBlob
import sys
import json
import base64
import random

try:
    data = json.loads(base64.b64decode(sys.argv[1]))
except:
    print "ERROR"
    sys.exit(1)

for key in data:
	testimonial = TextBlob(data[key])
	if testimonial.sentiment.polarity == 0:
		modifier = random.randint(1,10)/100.0-0.05
	else :
		modifier = 0
	data[key] = testimonial.sentiment.polarity + modifier
print json.dumps(data)