import { createContext, useContext, useState } from 'react';
import { GuideTabType } from '@shared/types/guide_types';

type GuideTabContainerProviderProps = {
  subjectId: number,
  currentTab: GuideTabType,
  children: React.ReactNode
}

export type GuideTabContainerType = {
  subjectId: number,
  currentTab: GuideTabType
}

export const GuideTabContainerContext = createContext<GuideTabContainerType | null>(null);

export const GuideTabContainerProvider = ({ subjectId, currentTab, children }: GuideTabContainerProviderProps) => {
  const value = {
    subjectId,
    currentTab
  };

  return (
    <GuideTabContainerContext.Provider value={value}>
      {children}
    </GuideTabContainerContext.Provider>
  );
}

export const useGuideTabContainer = () => {
  return useContext(GuideTabContainerContext);
}