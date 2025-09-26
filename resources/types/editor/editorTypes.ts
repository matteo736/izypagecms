import { Section } from "../content/pages/pagesType";

export interface EditorState {
    blocks: Section[];
    selectedBlockId: string | null;
    isDragging: boolean;
    history: {
        past: Section[][];
        future: Section[][];
    };
}