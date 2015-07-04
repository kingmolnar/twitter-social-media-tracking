#!/bin/env python
# -*- coding: utf-8 -*-

import oauth2 as oauth
import urllib2
import urllib
import json
import pprint

# See Assginment 6 instructions or README for how to get these credentials
access_token_key = ""
access_token_secret = ""

consumer_key = ""
consumer_secret = ""

_debug = 0

oauth_token    = oauth.Token(key=access_token_key, secret=access_token_secret)
oauth_consumer = oauth.Consumer(key=consumer_key, secret=consumer_secret)

signature_method_hmac_sha1 = oauth.SignatureMethod_HMAC_SHA1()

http_method = "GET"


http_handler  = urllib2.HTTPHandler(debuglevel=_debug)
https_handler = urllib2.HTTPSHandler(debuglevel=_debug)

pp = pprint.PrettyPrinter(indent=2)

'''
Construct, sign, and open a twitter request
using the hard-coded credentials above.
'''
def twitterreq(url, method, parameters):
  req = oauth.Request.from_consumer_and_token(oauth_consumer,
                                             token=oauth_token,
                                             http_method=http_method,
                                             http_url=url,
                                             parameters=parameters)

  req.sign_request(signature_method_hmac_sha1, oauth_consumer, oauth_token)

  headers = req.to_header()

  if http_method == "POST":
    encoded_post_data = req.to_postdata()
  else:
    encoded_post_data = None
    url = req.to_url()

  opener = urllib2.OpenerDirector()
  opener.add_handler(http_handler)
  opener.add_handler(https_handler)

  response = opener.open(url, encoded_post_data)

  return response

def fetchsamples(post_params):
  url = "https://api.twitter.com/1.1/users/lookup.json"
##  parameters = ['follow':'BSLADE,alcoholharmony,timdillinger,iRockFloyd,dannicassette,BertellYoung,FrescoKane,ShaShaJones,Enxo,DrewVision,QDaKid,JFuzion,therobmilton,iamdoughboy,mikekalombo,MylahMusic']
#  parameters = {
#	'follow': '123',
#	'track' : 'BSLADE,alcoholharmony,timdillinger,iRockFloyd,dannicassette,BertellYoung,FrescoKane,ShaShaJones,Enxo,DrewVision,QDaKid,JFuzion,therobmilton,iamdoughboy,mikekalombo,MylahMusic'
#				}
  response = twitterreq(url, "POST", post_params)
  for line in response:
	data = json.loads(line)
	for d in data:
	  print d['screen_name'], d['id']

if __name__ == '__main__':

  POST_PARAMS = {
	'screen_name' : 'bslade,alcoholharmony,timdillinger,irockfloyd,dannicassette,bertellyoung,frescokane,shashajones,enxo,drewvision,qdakid,jfuzion,therobmilton,iamdoughboy,mikekalombo,mylahmusic'
				}
  fetchsamples(POST_PARAMS)
