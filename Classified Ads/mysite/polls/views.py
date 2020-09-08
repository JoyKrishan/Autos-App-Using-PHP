from django.shortcuts import render, get_object_or_404
from django.http import HttpResponse, HttpResponseRedirect
from .models import Question, Choice
from django.urls import reverse
from django.views import generic

class IndexView(generic.ListView):
    template_name='polls/index.html'
    context_object_name='latest_question_list'

    def get_queryset(self):

        return Question.objects.all()

class DetailView(generic.DetailView):
    model=Question
    template_name= 'polls/detail.html'

class ResultsView(generic.DetailView):
    model=Question
    template_name='polls/results.html'

def vote(request, question_id):
    question= get_object_or_404(Question, pk=question_id)
    try:
        selected_choice=question.choice_set.get(pk=request.POST['choice'])
    except (KeyError, Choice.DoesNotExist):
        return render(request, 'polls/detail.html', {'question':question, 'error_message':"You did not select a choice"})
    selected_choice.votes +=1
    selected_choice.save()

    return HttpResponseRedirect(reverse('polls:results', args=[question.id]))

def owner(request):
       return HttpResponse("Hello, world. 52b738b3 is the polls owner.")