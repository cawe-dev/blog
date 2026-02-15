interface IChangelogItem {
    id: number
    title: string
    date: Date
    type: string
    version: strinf
    slot?: string
    formattedDate?: string
}

interface IChangelogs {
    ChangelogItem: IChangelogItem[]
}

export type { IChangelogItem, IChangelogs }