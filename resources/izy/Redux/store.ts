import { configureStore } from '@reduxjs/toolkit'
import { editorSlice, setBlocks } from './editorSlice'
// ...

export const store = configureStore({
  reducer: {
    editor: editorSlice.reducer,
  },
})

// Infer the `RootState` and `AppDispatch` types from the store itself
export type RootState = ReturnType<typeof store.getState>
// Inferred type
export type AppDispatch = typeof store.dispatch
// Inferred type 
export type AppStore = typeof store