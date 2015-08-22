# Instalacion:

## Bajar el proyecto:
```sh
git clone https://github.com/gonetil/cpm-programa-jym.git
git checkout working
bin/vendors install
```

## Modificar a mano los siguientes archivos:

### vendor/swiftmailer/lib/classes/Swift.php:62
cambiar

```php
    call_user_func($init)
```
por
```php
   if(!preg_match("/init.php$/i",$init)){
                   call_user_func($init);
          }
```

### vendor/doctrine/lib/Doctrine/ORM/Mapping/ClassMetadata.php:342
Cambiar
```php
    $this->_prototype = unserialize(sprintf('O:%d:"%s":0:{}', strlen($this->name), $this->name));
```    
por
```php
    $this->_prototype = @unserialize(sprintf('O:%d:"%s":0:{}', strlen($this->name), $this->name));
    if ($this->_prototype === false) {
          $this->_prototype = unserialize(sprintf('C:%d:"%s":0:{}', strlen($this->name), $this->name));
    }
```    
