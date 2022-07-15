import { createContext, useContext, useState } from 'react';

type SectionContainerProviderProps = {
  currentDraggingId: string|null,
  children: React.ReactNode
}

export type SectionContainerType = {
  currentDraggingId: string|null,
  currentEditablePluslet: string,
  setCurrentEditablePluslet: React.Dispatch<React.SetStateAction<string>>
}

export const SectionContainerContext = createContext<SectionContainerType | null>(null);

export const SectionContainerProvider = ({ currentDraggingId, children }: SectionContainerProviderProps) => {
  const [currentEditablePluslet, setCurrentEditablePluslet] = useState<string>('');

  const value = {
    currentDraggingId,
    currentEditablePluslet,
    setCurrentEditablePluslet
  };

  return (
    <SectionContainerContext.Provider value={value}>
      {children}
    </SectionContainerContext.Provider>
  );
}

export const useSectionContainer = () => {
  return useContext(SectionContainerContext);
}