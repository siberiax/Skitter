from django.shortcuts import render, redirect

def IndexView(request):
    return render(request, 'index.html', {})

def ProfileView(request, uid=None):
    data = {'uid': uid}
    return render(request, 'profile.html', data)

def UserListView(request, uid):
    data = {'uid': uid}
    return render(request, 'userlist.html', data)

def SettingsView(request, uid):
    data = {'uid': uid}
    return render(request, 'settings.html', data)