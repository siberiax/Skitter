from django.conf.urls import url
from django.contrib import admin
from .views import *

urlpatterns = [
    url(r'^$',                              
        IndexView,      name='index'),
    url(r'^profile/(?:(?P<uid>/d+)/)?',     
        ProfileView,    name='profile'),
    url(r'^profile/(?P<uid>/d+)/followers/',     
        UserListView,    name='followers'),
    url(r'^profile/(?P<uid>/d+)/following/',     
        UserListView,    name='following'),
    url(r'^settings/(?P<uid>/d+)/',         
        SettingsView,   name='settings'),
    url(r'^followers/(?P<uid>/d+)/',         
        SettingsView,   name='settings'),

    url(r'^admin/',                         
        admin.site.urls),
]
