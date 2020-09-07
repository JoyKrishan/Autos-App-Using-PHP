from django.shortcuts import render
from django.http import HttpResponse

def index(request):
    num_visits= request.session.get('num_visits',0)+1
    request.session['num_visits']=num_visits
    response=HttpResponse('view count='+str(num_visits))
    response.set_cookie('dj4e_cookie', '52b738b3', max_age=1000)
    if num_visits>5:del(request.session['num_visits'])
    return response
