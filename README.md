Instalacion:

Bajar el proyecto:
git clone ...
bin/vendors install

2. Archivo /vendor/swiftmailer/lib/classes/Swift.php:62
cambiar
    call_user_func($init)
por
    if(!preg_match("/init.php$/i",$init)){
                    call_user_func($init);
           }

3. Archivo /vendor/doctrine/lib/Doctrine/ORM/Mapping/ClassMetadata.php:342
Cambiar
    $this->_prototype = unserialize(sprintf('O:%d:"%s":0:{}', strlen($this->name), $this->name));
por
    $this->_prototype = @unserialize(sprintf('O:%d:"%s":0:{}', strlen($this->name), $this->name));
    if ($this->_prototype === false) {
          $this->_prototype = unserialize(sprintf('C:%d:"%s":0:{}', strlen($this->name), $this->name));
    }