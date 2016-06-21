# GeneratorBundle

## Commands

###Generate Behat Features 
<code>
app/console diside:generate:features AppBundle:EntityName
</code>

Options:

  - --force (-f)
  - --no-security
  - --no-filters

###Generate CRUD
<code>
app/console diside:generate:crud AppBundle:EntityName
</code>

Options:

  - --force (-f)
  - --no-security
  - --no-filters

###Generate Entity class 
<code>
app/console doctrine:generate:entities AppBundle:EntityName --path=src/ --no-backup
</code>

###Generate Form class 
<code>
app/console diside:generate:form AppBundle:EntityName
</code>

Options:

  - --force (-f)
  
###Generate FilterForm class 
<code>
app/console diside:generate:form AppBundle:EntityName --filter
</code>

Options:

  - --force (-f)

###Generate Controller class 
<code>
app/console diside:generate:controller AppBundle:EntityName
</code>

Options:

  - --force (-f)
  - --no-security
  - --no-filters

###Generate Views 
<code>
app/console diside:generate:views AppBundle:EntityName
</code>

Options:

  - --force (-f)
  - --no-security
  - --no-filters
