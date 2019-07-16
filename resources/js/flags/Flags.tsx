export const servers = (id: string) => ({
    loading: () => `servers.loading`,
    updating: (id2: string) => `servers.${id || id2}.updating`,
});