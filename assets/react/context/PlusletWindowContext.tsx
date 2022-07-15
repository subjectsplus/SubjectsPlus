import { createContext, useContext } from 'react';

type PlusletWindowProviderProps = {
  isEditMode: boolean,
  setIsEditMode: React.Dispatch<React.SetStateAction<boolean>>,
  savePlusletCallback: (data: object, toggleEditMode?: boolean) => void,
  isSaveRequested: boolean,
  children: React.ReactNode
}

export type PlusletWindowType = {
  isEditMode: boolean,
  setIsEditMode: React.Dispatch<React.SetStateAction<boolean>>,
  savePlusletCallback: (data: object, toggleEditMode?: boolean) => void,
  isSaveRequested: boolean
}

export const PlusletWindowContext = createContext<PlusletWindowType | null>(null);

export const PlusletWindowProvider = ({ isEditMode, setIsEditMode, savePlusletCallback, isSaveRequested, children }: PlusletWindowProviderProps) => {

  const value = {
    isEditMode,
    setIsEditMode,
    savePlusletCallback,
    isSaveRequested
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