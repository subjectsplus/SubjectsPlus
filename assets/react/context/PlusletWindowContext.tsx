import { createContext, useContext } from 'react';

type PlusletWindowProviderProps = {
  isEditMode: boolean,
  setIsEditMode: React.Dispatch<React.SetStateAction<boolean>>,
  children: React.ReactNode
}

export type PlusletWindowType = {
  isEditMode: boolean,
  setIsEditMode: React.Dispatch<React.SetStateAction<boolean>>
}

export const PlusletWindowContext = createContext<PlusletWindowType | null>(null);

export const PlusletWindowProvider = ({ isEditMode, setIsEditMode, children }: PlusletWindowProviderProps) => {

  const value = {
    isEditMode,
    setIsEditMode
  };

  return (
    <PlusletWindowContext.Provider value={value}>
      {children}
    </PlusletWindowContext.Provider>
  );
}

export const usePlusletWindow = () => {
  return useContext(PlusletWindowContext);
}