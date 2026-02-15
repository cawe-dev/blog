const formatRelativeTime = (date: Date | string): string => {
    const now = new Date()
    const target = new Date(date)
    const seconds = Math.floor((now.getTime() - target.getTime()) / 1000)

    if (seconds < 60) return 'agora'
    if (seconds < 3600) return `${Math.floor(seconds / 60)}m atrás`
    if (seconds < 86400) return `${Math.floor(seconds / 3600)}h atrás`
    if (seconds < 604800) return `${Math.floor(seconds / 86400)}d atrás`

    return target.toLocaleDateString('pt-BR')
}

function formatDate(dateString: string | null) {
    if (!dateString) {
        return 'Data não disponível'
    }
    return new Date(dateString).toLocaleDateString('pt-BR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    })
}

export { formatRelativeTime, formatDate }