import {
    DndContext,
    closestCenter,
    KeyboardSensor,
    PointerSensor,
    useSensor,
    useSensors
} from '@dnd-kit/core'
import {
    SortableContext,
    verticalListSortingStrategy,
    sortableKeyboardCoordinates
} from '@dnd-kit/sortable'
import { SortableBlock } from './SortableBlock'
import EditableElement from '../editableElement'

// Funzione per spostare un elemento in un array
// Questa funzione prende un array, un indice vecchio e un nuovo indice
// e restituisce un nuovo array con l'elemento spostato
// La funzione utilizza splice per rimuovere l'elemento dall'indice vecchio
// e lo inserisce nel nuovo indice
// Restituisce un nuovo array con l'elemento spostato
function arrayMove(array: any[], oldIndex: number, newIndex: number): any[] {
    const result = Array.from(array);
    const [removed] = result.splice(oldIndex, 1);
    result.splice(newIndex, 0, removed);
    return result;
}

// Componente principale
export default function DraggableEditor({ items, setItems }:
    { items: any[], setItems: (items: any) => void }) {
    // Sensori per input diversi
    const sensors = useSensors(
        // Sensore per il puntatore (mouse o touch)
        useSensor(PointerSensor,
            {
                activationConstraint: {
                    distance: 5, // distanza minima per iniziare il drag
                },
            }
        ),
        // Sensore per la tastiera
        useSensor(KeyboardSensor, {
            coordinateGetter: sortableKeyboardCoordinates
        })
    )
    // Funzione che gestisce il termine del drag
    // Questa funzione viene chiamata quando il drag termina
    // Prende come argomenti l'elemento attivo e l'elemento di destinazione
    // Se non c'è una destinazione, non fa nulla
    // Altrimenti, sposta l'elemento attivo nella nuova posizione
    // Utilizza la funzione arrayMove per spostare l'elemento
    // e aggiorna lo stato con il nuovo array
    const handleDragEnd = ({ active, over }: { active: any, over: any }) => {
        if (!over) return; // se non c'è una destinazione, non fare nulla
        const orderedItems = arrayMove(items, active.id, over.id);
        setItems(orderedItems);
    };

    return (
        /* DndContext è il contesto principale per il drag and drop
            * collisionDetection è la funzione che determina se un elemento
            * è sopra un altro elemento
            * onDragEnd è la funzione che gestisce il termine del drag
            */
        <DndContext
            sensors={sensors}
            collisionDetection={closestCenter}
            onDragEnd={handleDragEnd}
        >
            {/* SortableContext è il contesto per gli elementi ordinabili
                * items è l'array di elementi ordinabili
                * strategy è la strategia di ordinamento (in questo caso, verticale)
                */}
            <SortableContext
                items={items}
                strategy={verticalListSortingStrategy}
            >
                    {/* Mappiamo gli elementi e creiamo un SortableBlock per ognuno di essi
                    * Ogni SortableBlock ha una chiave unica (id) e un elemento Editabile
                    * Ogni elemento Editabile ha un tipo e un contenuto
                    */}
                {items.map(item => (
                    <SortableBlock key={item.id} id={item.id}>
                        <EditableElement type={item.type} content={item.content} />
                    </SortableBlock>
                ))}
            </SortableContext>
        </DndContext>
    )
}

