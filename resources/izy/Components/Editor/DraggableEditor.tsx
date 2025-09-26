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
import { EditorBlock } from '@types/editor/editorTypes';

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
    { items: EditorBlock[], setItems: (items: EditorBlock[]) => void }):
    JSX.Element {
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
    // Se l'elemento attivo è lo stesso della destinazione, non fa nulla
    // Altrimenti, sposta l'elemento attivo nella nuova posizione
    // Utilizza la funzione arrayMove per spostare l'elemento
    // e aggiorna lo stato con il nuovo array
    const handleDragEnd = ({ active, over }: { active: any, over: any }) => {
        if (!over) return;
        if (active.id === over.id) return;

        const oldIndex = items.findIndex(item => item.id === active.id);
        const newIndex = items.findIndex(item => item.id === over.id);
        if (oldIndex === -1 || newIndex === -1) return;

        const orderedItems = arrayMove(items, oldIndex, newIndex);
        setItems(orderedItems);
    };
    return (
        <div className='flex-col'>
        {/* DndContext è il contesto principale per il drag and drop
            * collisionDetection è la funzione che determina se un elemento
            * è sopra un altro elemento
            * onDragEnd è la funzione che gestisce il termine del drag
            */}
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
                            <EditableElement id={item.id} type={item.type} content={item.content} />
                        </SortableBlock>
                    ))}
                </SortableContext>
                {/* Blocco finale fisso */}
                <div
                    className="my-4 p-4 text-muted-foreground text-sm italic cursor-pointer hover:text-foreground hover:bg-accent rounded-md transition"
                    onClick={() => {
                        const newItem = {
                            id: items.length + 1,
                            type: 'p' as keyof JSX.IntrinsicElements,
                            content: "",
                        };
                        setItems([...items, newItem]);
                    }}
                >
                    + Aggiungi blocco o scrivi qualcosa...
                </div>
            </DndContext>
        </div>
    )
}

