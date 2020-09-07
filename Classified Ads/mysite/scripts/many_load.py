import csv  # https://docs.python.org/3/library/csv.html

# https://django-extensions.readthedocs.io/en/latest/runscript.html

# python3 manage.py runscript many_load

from unesco.models import Site, Category, State,Region, Iso

def run():
    fhand = open('World_sites.csv')
    reader = csv.reader(fhand)
    next(reader) # Advance past the header

    Site.objects.all().delete()
    Category.objects.all().delete()
    State.objects.all().delete()
    Region.objects.all().delete()
    Iso.objects.all().delete()

    # Format
    # Site_name,description,justification,year,longitude,latitude,area_hectares,category,states,region,iso

    for row in reader:
        count=0

        print(row)
        print(count)

        site_name=row[0]
        desc=row[1]
        just=row[2]
        try:
            year=int(row[3])
        except:
            year=None
        try:
            longitude=float(row[4])
        except:
            longitude=None
        try:
            latitude=float(row[5])
        except:
            latitude=None
        try:
            area=float(row[6])
        except:
            area=None

        cat=row[7]
        stat=row[8]
        reg=row[9]
        iso=row[10]


        ISO, created=Iso.objects.get_or_create(name=iso)
        region, created=Region.objects.get_or_create(name=reg)
        state, create=State.objects.get_or_create(name=stat, region=region)
        category, created=Category.objects.get_or_create(name=cat)
        print('HIIIIIIIII')
        site=Site(name=site_name, just=just, desc=desc, year=year, longitude=longitude, latitude=latitude , area_hectares=area, category=category, state=state, iso=ISO)
        site.save()
        count=count+1
