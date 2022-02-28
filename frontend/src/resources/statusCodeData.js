import { useMemo } from "react";

export default function useRows() {
 const rows = useMemo(
   () => [
      {
        code: "200",
        answer: "suscessfull",
        description: "La solicitud ha tenido éxito",
        solution: " "
      },
     {
       code: "201",
       answer: "ítem_created",
       description: "Se ha creado el contenido de forma exitosa",
       solution: ""
     },
     {
        code: "400",
        answer: "invalid_credentials",
        description: "El servidor no puede interpretar la solicitud debido " +
                     "a errores de sintáxis",
        solution: "Revisar que los datos ingresados sean correctos " +
                  "e intentar nuevamente"
     },
     {
        code: "404",
        answer: "ítem_not_found",
        description: "No existe el contenido solicitado",
        solution: "Realizar la búsqueda nuevamente verificando " +
                  "que los valores sean correctos, de lo contrario " + 
                  "añadir nuevo contenido"
     },
     {
        code: "409",
        answer: "conflict",
        description: "Error al intentar añadir contenido, debido " +
                     "a previa existencia de este",
        solution: "Modificar el contenido existente o añadir nuevo"
     },
     {
        code: "500",
        answer: "could_not_create_token",
        description: "Error interno del servidor",
        solution: ""
     },
   ],
   []
 );

 return rows;
}