# Fichame

En este proyecto retomaremos el código entregado en el ejercicio en clase
[Fichame](https://github.com/rIftimie/fichame) y trataremos de llevar a cabo diferentes
implementaciones y correciones -> 

# Tabla de contenidos
- [Vista Trabajadores](#vista-trabajadores)
  - [Información visible](#información-visible)
  - [Pendiente por ver ](#pendiente-por-ver)
- [Vista Admin](#vista-admin)
  - [La información visible](#la-información-visible)
  - [Edición de Evento](#edición-de-evento)
- [Trabajo diario](#trabajo-diario)
- [Partes confusas](#partes-confusas)

## Vista Trabajadores

En esta vista los trabajadores podrán ver una lista con los eventos ordenados por meses.

Las condiciones más destacables son las siguientes:

- Si empezó justo en el mes anterior, pero termina en este, debe aparecer en la lista de eventos de este mes.
- Si empieza este mes o en los meses siguientes, debe aparecer en el mes que le corresponda.

### Información visible

1. Estado: (contiene iconos)
    + Pendiente de disponibilidad: círculo amarillo
    + Disponibilidad marcada pero no seleccionado: círculo gris
    + Disponibilidad marcada como no disponible: círculo negro
    + Disponibilidad marcada y seleccionado: cŕiculo verde.
2. Extra
    - Icono de coordinación (usuario es un coordinador)
    - Icono de conducción (usuario es un conductor)
3. Nombre
4. Tipo
    + Pueden ser múltiples
    + Cada uno tiene su icono
5. Número de trabajadores 
6. Fecha (Tanto de inicio como de fin)
7. Horario Estimado
8. Horas Estimadas
9. Horas Reales
10. Sueldo Estimado (Se calcula con las horas estimadas y si hay o no conducción)
11. Sueldo Real (Se calcula con las horas reales + conducción + coordinación)
12. Asistencia - - -
13. Botón Formulari con enlace a la encuesta del evento, visible si ha sido seleccionado.

### Pendiente por ver 

1. Ver los eventos pasados 
2. Filtros

## Vista Admin

En esta vista podremos ver algo similar a la vista de trabajadores salvo por unas cosas extra.

Cuando se crea un evento se guarda como borrador por defecto.

### La información visible

1. Estado: (contiene iconos)
    + Este evento aún está marcado como borrador (no les aparece a los trabajadores): circulo amarillo
    + Aún no se han seleccionado los trabajadores que irán, o falta asignar suficientes conductores o el coordinador : círculo rojo
    + Se han seleccionado todos los trabajadores que irán, y se han asignado: círculo verde.
2. Nombre
3. Fecha (Tanto de inicio como de fin)
4. Tipo
    + Pueden ser múltiples
    + Cada uno tiene su icono
5. Número de trabajadores (Se deben mostrar las que han confirmado asistencia con respecto al total)
6. Número de trabajadores pendientes (faltan por marcar la disponibilidad)
7. Horario Estimado
8. Horas Estimadas
9. Botón para editar el evento
10. Botón para borrar el evento (debe aparecer un pop-up para confirmar, los eventos cerrados o publicado no puede borrarse "solo borradores")


### Edición de Evento

Los campos editables son:

+ Nombre
+ Tipo (se puede marcar más de uno)
+ Fecha Inicio
+ Fecha fin
+ Número de trabajadores
+ Conductores necesarios
+ Distancia al evento
+ Horario estimado
+ Horas totales estimadas
+ Enlace al formulario (documento de drive)

Añadimos botones para :

Guardar los cambios, publicar evento (como borrador), cerrar evento y borrar evento (solo borradores).

Adicionalmente debe aparecer un tabla con los usuarios que han marcado su asistencia, esta tabla debe contener los siguientes campos:

+ Nombre
+ Asistencia ( ? (por defecto marcado), ✓(asiste al evento) ✘ (no asiste al evento) )
+ Coordinador (si anteriormente marca que asiste puede marcar ser coordinador) 
+ Conductor (si en su perfil pone que puede conducir y asiste al evento puede marcar ser conductor) - - -
+ Coche particular (solo se puede marcar si asiste y es conductor)
+ Horario estimado (similar al horario del evento pero sirve para sobreescribir si tiene un horario especial)
+ Modificador de horas extra

Finalmente añadimos campos internos que almacenen cuando fue que se creó el evento y cuando se actualizó por última vez y también añadir un campo que almacene cuando se marca que puede asistir al evento.

## Trabajo diario

- Clonación del proyecto
- Establecimiento de las ramas secundarias y principal
- Corrección de campos erróneos y duplicados 
- Correción de la jerarquía de carpetas
- Repaso del proyecto (para entender la funcionabilidad)
- Creación de nuevos campos en la base de datos
- Corrección de los estilos (responsive)
- Implementado Vista de eventos para trabajadores donde se pueden ver ordenados por meses con las respectivas condiciones, adicionalmente se ha creado una lista de trabajadores por eventos.
- Desplegable con las opciones de disponibilidad al igual que la asistencia( esta de momento se puede editar no es una única vez)


## Partes confusas 

Los horarios, sueldo , asistencia y disponibilidad , la marca de conductor en el user, también si hay campos no editables para un evento como luego hay funciones que derivan de estos, horas extras. 
