import { useSortable } from '@dnd-kit/sortable'
import { CSS } from '@dnd-kit/utilities'
import { PropsWithChildren } from 'react'

export const SortableBlock = ({ id, children }: PropsWithChildren<{ id: number }>) => {
  const {
    attributes,
    listeners,
    setNodeRef,
    transform,
    transition,
    isDragging
  } = useSortable({ id: id })

  const style = {
    transform: CSS.Transform.toString(transform),
    // Rimuoviamo transition per evitare problemi durante il drag
    opacity: isDragging ? 0.5 : 1,
    display: 'block',
    width: '100%',
  }

  return (
    <div
      ref={setNodeRef}
      style={style}
      className="relative group"
    >
      <div
        {...listeners}
        {...attributes}
        className="drag-handle absolute left-0 top-1/2 -translate-y-1/2 -translate-x-full opacity-0 group-hover:opacity-100 cursor-grab p-2"
      >
        ⠿
      </div>
      <div className="pointer-events-auto">
        {children}
      </div>
    </div>
  )
}