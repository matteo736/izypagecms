export interface EditorBlock {
    id: number;
    type: keyof JSX.IntrinsicElements;
    content: string;
    settings?: Record<string, any>;
}

export interface EditorState {
    blocks: EditorBlock[];
    selectedBlockId: string | null;
    isDragging: boolean;
    history: {
        past: EditorBlock[][];
        future: EditorBlock[][];
    };
}