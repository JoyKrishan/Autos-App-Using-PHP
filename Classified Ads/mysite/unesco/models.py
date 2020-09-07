from django.db import models

# Create your models here.

class Iso(models.Model):
    name=models.CharField(max_length=4)

    def __str__(self):
        return self.name


class Region(models.Model):
    name=models.CharField(max_length=128)

    def __str__(self):
        return self.name


class State(models.Model):
    name=models.CharField(max_length=128)
    region=models.ForeignKey('Region', on_delete=models.CASCADE)


    def __str__(self):
        return self.name


class Category(models.Model):
    name=models.CharField(max_length=128)

    def __str__(self):
        return self.name


class Site(models.Model):
    name=models.CharField(max_length=128)
    just=models.TextField(null=True)
    desc=models.TextField(null=True)
    year=models.IntegerField(null=True)
    longitude=models.FloatField(null=True)
    latitude=models.FloatField(null=True)
    area_hectares=models.FloatField(null=True)
    category=models.ForeignKey('Category', on_delete=models.CASCADE)
    state=models.ForeignKey('State', on_delete=models.CASCADE)
    iso=models.ForeignKey('Iso', on_delete=models.CASCADE)

    def __str__(self):
        return self.name
