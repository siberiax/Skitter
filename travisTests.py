import requests
from elasticsearch import Elasticsearch

session = requests.Session()

def cred():
    f = open(".cred.txt")
    p = f.readline().strip()
    print p
    return p

def login():
    r = session.post("http://35.166.55.42/login", data={'username': 'agm6856', 'password': cred()})
    print "LOGIN RESPONSE: " + str(r.status_code)
    return session.cookies.get_dict()['JSESSIONID']

def welcome():
    r = session.post("http://35.166.55.42/welcome", data={'username': 'agm6856'})
    print r.status_code

def isAuthenticated(sessionid):
    r = session.get("http://35.166.55.42/isAuthenticated?sessionId=" + sessionid)
    print "ISAUTHENTICATED RESPONSE: " + str(r.status_code)
    r = session.get("http://35.166.55.42/isAuthenticated?sessionId=" + sessionid + "1")
    print "BAD ISAUTHENTICATED RESPONSE: " + str(r.status_code)


def addSkit():
    r = session.post("http://35.166.55.42/addSkit", data={'skit': 'travis test skit', 'id': '1'})
    if (r.text == "ADDED SKIT!"):
        print ("ADD SKIT GOOD!")

def removeSkit():
    r = session.post("http://35.166.55.42/addSkit", data={'skitid': '1'})
    if (r.text == "skit removed"):
        print ("REMOVE SKIT GOOD!")

def followUser():
    r = session.post("http://35.166.55.42/followUser", data={'userID': '3'})
    r2 = session.post("http://35.166.55.42/getFollowing", data={'userID': '10'})
    if ("'id': 3" in r2.text):
        print "FOLLOW USER WORKED"

def unfollowUser():
    r = session.post("http://35.166.55.42/unfollowUser", data={'userID': '3'})
    r2 = session.post("http://35.166.55.42/getFollowing", data={'userID': '10'})
    if ("'id': 3" not in r2.text):
        print "UNFOLLOW USER WORKED"

def changeEmail():
    r = session.post("http://35.166.55.42/changeEmail", data={'email': 'travis@email.com'})
    if (r.text == "Updated Email"):
        print "CHANGE EMAIL WORKED"

def changeDisplayName():
    r = session.post("http://35.166.55.42/changeDisplayName", data={'displayName': 'travisTheMan'})
    if (r.text == "Updated Display Name"):
        print "CHANGE DISPLAY NAME WORKED"

def main():
    sessionid = login()
    welcome()
    isAuthenticated(sessionid)
    addSkit()
    removeSkit()
    followUser()
    unfollowUser()
    changeEmail()
    changeDisplayName()

main()
