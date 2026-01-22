export interface WebsiteStatus {
  website_id: number
  current_status: 'up' | 'down' | 'unknown'
  last_checked_at: string | null
  alert_sent: boolean
  alert_sent_at: string | null
}

export interface Website {
  uuid: string
  url: string
  current_status: WebsiteStatus | null
}
