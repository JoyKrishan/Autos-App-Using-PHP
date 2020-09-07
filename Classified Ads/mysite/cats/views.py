from django.contrib.auth.mixins import LoginRequiredMixin
from django.shortcuts import render, redirect, get_object_or_404
from django.views import View
from django.views.generic.edit import CreateView, UpdateView, DeleteView
from django.urls import reverse_lazy

from cats.models import Cat, Breed

class MainView(LoginRequiredMixin,View):
    def get(self, request):
        ct=Cat.objects.all()
        br=Breed.objects.all().count()
        context={'cats':ct, 'breed_count':br}

        return render(request, 'cats/cat_view.html', context)

class BreedView(LoginRequiredMixin, View):
    def get(self, request):
        br=Breed.objects.all()

        return render(request, 'cats/breed_view.html', {'breed_list':br})

class CatCreate(LoginRequiredMixin, CreateView):
    model=Cat
    fields='__all__'
    success_url=reverse_lazy('cats:all')

class CatUpdate(LoginRequiredMixin, UpdateView):
    model=Cat
    fields='__all__'
    success_url=reverse_lazy('cats:all')

class CatDelete(LoginRequiredMixin, DeleteView):
    model=Cat
    fields='__all__'
    success_url=reverse_lazy('cats:all')

class BreedCreate(LoginRequiredMixin, CreateView):
    model=Breed
    fields='__all__'
    success_url=reverse_lazy('cats:all')

class BreedUpdate(LoginRequiredMixin, UpdateView):
    model=Breed
    fields='__all__'
    success_url=reverse_lazy('cats:all')

class BreedDelete(LoginRequiredMixin, DeleteView):
    model=Breed
    fields='__all__'
    success_url=reverse_lazy('cats:all')
