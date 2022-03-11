# M133 Theorie

## Projektphasen

* Analyse
* Design/Entwurf
* Implementation
* Einführung
* Managment
* Dokumentation


## Projekt verwendbar
* javascript framework
* css framework
* php pure
	* ein globale App Klasse, anstatt Config oder so Globale Vars

## Testen

* positive sowie auch negative Tests
	* negativ: probieren zu kaputt machen
	* positiv: erreichte ziele...

## Clean Code

* descriptive naming is important
* write whole words for variables
* don't write `carMake` in a `Car` class -> unneeded context
* function, if parameter list longer than ~4 use ConfigClass
* function should do one thing, naming verbName
* write class Methods for if Statements (isThing), deduplication
* don't check the type, use parameters with types
* verschachelungen gering halten (wenig nested if/else)