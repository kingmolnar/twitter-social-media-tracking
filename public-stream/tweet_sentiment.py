#!/bin/env python

import sys
import json

def hw():
    print 'Hello, world!'

def lines(fp):
    print str(len(fp.readlines()))

def main():
	sent_file = open(sys.argv[1], "r")
	tweet_file = open(sys.argv[2])
	hw()
##	lines(sent_file)
##	lines(tweet_file)
	sent_table = {}
	for s in sent_file:
		(key, val) = s.split('\t')
		sent_table[key] = val

	print sent_table.keys();
	for t in tweet_file:
		j = json.loads(t)
		print dir(j)




if __name__ == '__main__':
    main()
