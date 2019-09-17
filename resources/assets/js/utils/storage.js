export const STORAGE_KEYS = {
    CONFIG: 'TIMELESS:CONFIG'
};

export const getConfig = () => {        
    const storageData = JSON.parse(localStorage.getItem(STORAGE_KEYS.CONFIG));
   
    return storageData;
};
export const setConfig = config => {
    return localStorage.setItem(STORAGE_KEYS.CONFIG, JSON.stringify(config));
};
