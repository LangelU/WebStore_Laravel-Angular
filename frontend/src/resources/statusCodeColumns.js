import { useMemo } from "react";

export default function useColumns() {
 const columns = useMemo(
   () => [
     {
       Header: "Código",
       accessor: "code"
     },
     {
       Header: "Respuesta",
       accessor: "answer"
     },
     {
       Header: "Descripción",
       accessor: "description"
     },
     {
       Header: "Posible solución",
       accessor: "solution"
     }
   ],
   []
 );

 return columns;
}