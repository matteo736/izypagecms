import { HtmlTag } from '@/Components/Blocks/htmltags';
import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import { EditorBlock, EditorState } from '@types/editor/editorTypes';

const initialState: EditorState = {
    blocks: [],
    selectedBlockId: null,
    isDragging: false,
    history: {
        past: [],
        future: []
    }
};

export const editorSlice = createSlice({
    name: 'editor',
    initialState,
    reducers: {
        setBlocks: (state, action: PayloadAction<EditorBlock[]>) => {
            // Salva lo stato corrente prima della modifica
            state.history.past.push([...state.blocks]);
            state.history.future = [];
            // Applica la modifica
            state.blocks = action.payload;
        },
        addBlock: (state, action: PayloadAction<EditorBlock>) => {
            // Salva lo stato corrente prima della modifica
            state.history.past.push([...state.blocks]);
            state.history.future = [];
            // Applica la modifica
            state.blocks.push(action.payload);
        },
        updateBlock: (state, action: PayloadAction<{ id: number; content: string }>) => {
            const block = state.blocks.find(b => b.id === action.payload.id);
            if (block) {
                // Salva lo stato corrente prima della modifica
                state.history.past.push([...state.blocks]);
                state.history.future = [];
                // Applica la modifica
                block.content = action.payload.content;
            } else {
                console.warn('Block non trovato:', action.payload.id);
            }
        },
        changeBlock: (state, action: PayloadAction<{ id: number; type: HtmlTag }>) =>{
            const block = state.blocks.find(b => b.id === action.payload.id);
            if (block) {
                // Salva lo stato corrente prima della modifica
                state.history.past.push([...state.blocks]);
                state.history.future = [];
                // Applica la modifica
                block.type = action.payload.type;
            } else {
                console.warn('Block non trovato:', action.payload.id);
            }
        },
        deleteBlock: (state, action: PayloadAction<number>) => {
            // Salva lo stato corrente prima della modifica
            state.history.past.push([...state.blocks]);
            state.history.future = [];
            // Applica la modifica
            state.blocks = state.blocks.filter(b => b.id !== action.payload);
        },
        setSelectedBlock: (state, action: PayloadAction<string | null>) => {
            state.selectedBlockId = action.payload;
        },
        setDragging: (state, action: PayloadAction<boolean>) => {
            state.isDragging = action.payload;
        },
        undo: (state) => {
            if (state.history.past.length > 0) {
                const previous = state.history.past[state.history.past.length - 1];
                state.history.future.unshift([...state.blocks]);
                state.blocks = previous;
                state.history.past.pop();
            }
        },
        redo: (state) => {
            if (state.history.future.length > 0) {
                const next = state.history.future[0];
                state.history.past.push([...state.blocks]);
                state.blocks = next;
                state.history.future.shift();
            }
        }
    }
});

export const { 
    setBlocks,
    addBlock, 
    updateBlock, 
    deleteBlock, 
    setSelectedBlock, 
    setDragging,
    undo,
    redo
} = editorSlice.actions;

export default editorSlice.reducer;