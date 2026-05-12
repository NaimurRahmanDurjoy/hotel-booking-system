import os

# XML Template for Draw.io
xml_template = """<mxfile>
  <diagram name="{name}">
    <mxGraphModel dx="1200" dy="1200" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="827" pageHeight="1169" math="0" shadow="0">
      <root>
        <mxCell id="0" />
        <mxCell id="1" parent="0" />
{content}
      </root>
    </mxGraphModel>
  </diagram>
</mxfile>"""

def escape_xml(text):
    return text.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;').replace('"', '&quot;').replace("'", '&apos;')

def node(id, value, style, x, y, w, h, parent="1"):
    safe_value = escape_xml(value)
    return f'        <mxCell id="{id}" value="{safe_value}" style="{style}" vertex="1" parent="{parent}"><mxGeometry x="{x}" y="{y}" width="{w}" height="{h}" as="geometry" /></mxCell>\n'

def edge(id, value, source, target, exitX=0.5, exitY=1, entryX=0.5, entryY=0, extra_pts="", parent="1"):
    safe_value = escape_xml(value)
    pts = f'<Array as="points">{extra_pts}</Array>' if extra_pts else ""
    return f'''        <mxCell id="{id}" value="{safe_value}" style="endArrow=classic;html=1;strokeColor=#000000;strokeWidth=1;rounded=0;labelBackgroundColor=#ffffff;exitX={exitX};exitY={exitY};entryX={entryX};entryY={entryY};" edge="1" parent="{parent}" source="{source}" target="{target}">
          <mxGeometry relative="1" as="geometry">{pts}</mxGeometry>
        </mxCell>\n'''

# Styles
s_lane = 'swimlane;html=1;childLayout=stackLayout;horizontal=1;startSize=30;horizontalStack=1;resizeParent=1;resizeParentMax=0;resizeLast=0;collapsible=1;marginBottom=0;'
s_sublane = 'swimlane;html=1;startSize=30;'
s_action = 'ellipse;whiteSpace=wrap;html=1;fillColor=#ffffff;strokeColor=#000000;verticalAlign=middle;'
s_decision = 'rhombus;whiteSpace=wrap;html=1;fillColor=#ffffff;strokeColor=#000000;'
s_start = 'ellipse;whiteSpace=wrap;html=1;aspect=fixed;fillColor=#000000;strokeColor=none;'
s_end = 'ellipse;html=1;shape=endState;fillColor=#000000;strokeColor=#000000;'

# 3. Room Booking Swimlane
def room_booking_swimlane():
    c = node("pool", "Room Booking Process", s_lane, 50, 50, 600, 800)
    c += node("lane_u", "User", s_sublane, 0, 0, 300, 800, "pool")
    c += node("lane_s", "System", s_sublane, 300, 0, 300, 800, "pool")
    c += node("start", "", s_start, 135, 40, 30, 30, "lane_u")
    c += node("u1", "Select Hotel & Room", s_action, 50, 100, 200, 60, "lane_u")
    c += node("u2", "Input check-in/out dates", s_action, 50, 200, 200, 60, "lane_u")
    c += node("s1", "Check Room Availability", s_action, 50, 200, 200, 60, "lane_s")
    c += node("d1", "Available?", s_decision, 100, 350, 100, 80, "lane_s")
    c += node("s2", "Show booking confirmation", s_action, 50, 500, 200, 60, "lane_s")
    c += node("u3", "Proceed to payment", s_action, 50, 500, 200, 60, "lane_u")
    c += node("end", "", s_end, 135, 650, 30, 30, "lane_s")
    
    c += edge("e1", "", "start", "u1", parent="lane_u")
    c += edge("e2", "", "u1", "u2", parent="lane_u")
    c += edge("e3", "", "u2", "s1", 1, 0.5, 0, 0.5, parent="pool")
    c += edge("e4", "", "s1", "d1", parent="lane_s")
    c += edge("e5", "Yes", "d1", "s2", parent="lane_s")
    c += edge("e6", "No", "d1", "u1", 0, 0.5, 0.5, 1, "<mxPoint x='240' y='390'/><mxPoint x='240' y='180'/>", parent="pool")
    c += edge("e7", "", "s2", "u3", 0, 0.5, 1, 0.5, parent="pool")
    c += edge("e8", "", "u3", "end", 0.5, 1, 0.5, 0, "<mxPoint x='150' y='600'/><mxPoint x='435' y='600'/>", parent="pool")
    return xml_template.replace("{name}", "Room Booking Swimlane").replace("{content}", c)

# 4. View Info Swimlane
def view_info_swimlane():
    c = node("pool", "View Info Process", s_lane, 50, 50, 600, 700)
    c += node("lane_u", "User", s_sublane, 0, 0, 300, 700, "pool")
    c += node("lane_s", "System", s_sublane, 300, 0, 300, 700, "pool")
    c += node("start", "", s_start, 135, 40, 30, 30, "lane_u")
    c += node("u1", "Click Hotel/Car Info", s_action, 50, 100, 200, 60, "lane_u")
    c += node("s1", "Redirect to details page", s_action, 50, 100, 200, 60, "lane_s")
    c += node("u2", "View fare, schedule & details", s_action, 50, 250, 200, 60, "lane_u")
    c += node("end", "", s_end, 135, 400, 30, 30, "lane_s")
    
    c += edge("e1", "", "start", "u1", parent="lane_u")
    c += edge("e2", "", "u1", "s1", 1, 0.5, 0, 0.5, parent="pool")
    c += edge("e3", "", "s1", "u2", 0, 0.5, 1, 0.5, "<mxPoint x='350' y='200'/><mxPoint x='150' y='200'/>", parent="pool")
    c += edge("e4", "", "u2", "end", 0.5, 1, 0.5, 0, "<mxPoint x='150' y='350'/><mxPoint x='435' y='350'/>", parent="pool")
    return xml_template.replace("{name}", "View Info Swimlane").replace("{content}", c)

# 5. Manage Customer Swimlane
def manage_customer_swimlane():
    c = node("pool", "Manage Profile", s_lane, 50, 50, 600, 700)
    c += node("lane_u", "User", s_sublane, 0, 0, 300, 700, "pool")
    c += node("lane_s", "System", s_sublane, 300, 0, 300, 700, "pool")
    c += node("start", "", s_start, 135, 40, 30, 30, "lane_u")
    c += node("u1", "Click on profile", s_action, 50, 100, 200, 60, "lane_u")
    c += node("s1", "Fetch user data from DB", s_action, 50, 100, 200, 60, "lane_s")
    c += node("u2", "Update profile details", s_action, 50, 250, 200, 60, "lane_u")
    c += node("s2", "Save updated info", s_action, 50, 250, 200, 60, "lane_s")
    c += node("end", "", s_end, 135, 400, 30, 30, "lane_s")
    
    c += edge("e1", "", "start", "u1", parent="lane_u")
    c += edge("e2", "", "u1", "s1", 1, 0.5, 0, 0.5, parent="pool")
    c += edge("e3", "", "s1", "u2", 0, 0.5, 1, 0.5, "<mxPoint x='350' y='200'/><mxPoint x='150' y='200'/>", parent="pool")
    c += edge("e4", "", "u2", "s2", 1, 0.5, 0, 0.5, parent="pool")
    c += edge("e5", "", "s2", "end", parent="lane_s")
    return xml_template.replace("{name}", "Manage Customer Swimlane").replace("{content}", c)

# Write files
with open("Grand_Azure_RoomBooking_Swimlane.drawio", "w") as f:
    f.write(room_booking_swimlane())
with open("Grand_Azure_ViewProfile_Swimlane.drawio", "w") as f:
    f.write(view_info_swimlane())
with open("Grand_Azure_ManageCustomer_Swimlane.drawio", "w") as f:
    f.write(manage_customer_swimlane())
